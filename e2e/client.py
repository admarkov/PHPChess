import requests
import json


class ApiResponse:

    def __init__(self, response):
        self.code = response.status_code
        self.data = json.loads(response.content)


class ChessApi:

    def __init__(self, host):
        self.host = host

    def status(self, game_id):
        return ApiResponse(requests.get(f'http://{self.host}/api/status.php', params={
            'game_id': game_id
        }))

    def start(self):
        return ApiResponse(requests.post(f'http://{self.host}/api/start.php'))

    def move(self, game_id, start, end):
        return ApiResponse(requests.post(f'http://{self.host}/api/move.php', data={
            'game_id': game_id,
            'from': start,
            'to': end
        }))
