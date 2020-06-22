import argparse

import client
import util


class GameRunner:

    def __init__(self, host, input, output, game_id=None, interactive_mode=False):
        self.api = client.ChessApi(host)
        self.game_id = game_id
        self.input = None
        if interactive_mode:
            self.input = open(input, 'w')
        else:
            self.input = open(input, 'r')
        self.output = open(output, 'w')
        self.interactive_mode = interactive_mode

    def replace_game_id(self, response):
        if response.payload and 'game_id' in response.payload and response.payload['game_id'] == self.game_id:
            response.payload['game_id'] = '%GAME_ID%'
        return response

    def start(self):
        self.write_response('-- START --\r\n')
        game = self.api.start()
        if game.code // 100 == 2:
            self.game_id = game.payload['game_id']
        self.write_response(util.dump_start(self.replace_game_id(game)))

    def status(self):
        self.write_response('-- STATUS --\r\n')
        response = self.api.status(self.game_id)
        self.write_response(util.dump_status(response))

    def move(self, begin, end):
        self.write_response(f'-- MOVE {begin}{end} --\r\n')
        response = self.api.move(self.game_id, begin, end)
        self.write_response(util.dump_move(self.replace_game_id(response)))
        if not self.interactive_mode:
            print(begin, end)

    def write_response(self, resp):
        if self.interactive_mode:
            print(resp)
        if self.output:
            self.output.write(resp.replace('\r\n', '\n'))

    def run_interactive(self):
        cmd = ''
        while cmd != 'end':
            cmd = input()
            if cmd != 'end':
                s, f = cmd.split()
                self.input.write(f'{s} {f}\n')
                self.move(s, f)
                self.status()

    def run_from_file(self):
        for line in self.input.readlines():
            s, f = line.split()
            self.move(s, f)
            self.status()

    def run(self):
        if not self.game_id:
            self.start()
        if self.interactive_mode:
            self.run_interactive()
        else:
            self.run_from_file()


if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--host', help='backend host', required=True)
    parser.add_argument('-i', '--input', help='input file with moves sequence', required=True)
    parser.add_argument('-o', '--output', help='output files for responses sequence', required=True)
    parser.add_argument('--interactive', help="interactive mode", action='store_true')
    parser.add_argument('action', type=str, help='one of `canon`, `test`')
    args = parser.parse_args()

    if args.action == 'canon':
        runner = GameRunner(args.host, args.input, args.output, interactive_mode=args.interactive)
        runner.run()
