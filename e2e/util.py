def chess_notataion_to_YX(coord):
    x = ord(coord[0]) - ord('A')
    y = ord(coord[1]) - ord('1')
    return y, x


def encode_piece(clr, type):
    res = ''
    if clr == 'white':
        res += 'W'
    elif clr == 'black':
        res += 'B'
    else:
        raise Exception('incorrect color')
    if type == 'king':
        res += 'K'
    elif type == 'queen':
        res += 'Q'
    elif type == 'bishop':
        res += 'B'
    elif type == 'knight':
        res += 'k'
    elif type == 'rook':
        res += 'R'
    elif type == 'pawn':
        res += 'P'
    else:
        raise Exception('incorrect type')
    return res


def pieces_map_from_list(pieces):
    m = [['..' for i in range(8)] for j in range(8)]
    for piece in pieces:
        y, x = chess_notataion_to_YX(piece['coordinate'])
        m[y][x] = encode_piece(piece['color'], piece['type'])
    return m


def pprints_board(board):
    res = ''
    for i in range(8):
        res += str(7 - i + 1) + ' ' + ' '.join(board[7 - i]) + '\r\n'
    res += '  A  B  C  D  E  F  G  H\r\n'
    return res


def dump_response(response, fields=[]):
    res = ''
    res += 'Response code: ' + str(response.code) + '\r\n'
    res += 'Response status: ' + response.data['status'] + '\r\n'
    if response.data['status'] == 'ok':
        for field in fields:
            res += f'{field[0]}: {response.data["payload"].get(field[1], "not presented")}\r\n'
    else:
        res += 'Error code: ' + str(response.data['error']['code']) + '\r\n'
        res += 'Message: ' + response.data['error'].get('message', 'NO_MESSAGE') + '\r\n'
    return res


def dump_status(status):
    if ('payload' in status.data):
        status.data['payload']['board'] = \
            '\r\n' + pprints_board(pieces_map_from_list(status.data['payload']['pieces']))
        del status.data['payload']['pieces']
    return dump_response(status, [
        ('Game status', 'game_status'),
        ('Active player', 'active_player'),
        ('Board', 'board')
    ])


def dump_start(response):
    return dump_response(response, [
        ('Game id', 'game_id')
    ])


def dump_move(response):
    return dump_response(response)

