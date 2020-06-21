import argparse
import json

import client
import util

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--host', help='backend host', required=True)
    parser.add_argument('-g', '--game-id', help='game id')
    parser.add_argument('action', type=str, help='one of `status`, `start`, `move`')
    parser.add_argument('-s', '--fro', help='start cell of move')
    parser.add_argument('-f', '--to', help='finish cell of move')
    args = parser.parse_args()

    api = client.ChessApi(args.host)

    if args.action == 'status':
        status = api.status(args.game_id)
        print(util.dump_status(status))
    if args.action == 'start':
        result = api.start()
        print(util.dump_start(result))
    if args.action == 'move':
        result = api.move(args.game_id, args.fro, args.to)
        print(util.dump_move(result))
