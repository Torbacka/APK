from flask import Flask, request

from service.apk_calculator import calculate_and_store

app = Flask(__name__)


def recalculate(request):
    calculate_and_store()
    pass


@app.route('/recalculate', methods=['GET'])
def local_recalculate():
    recalculate(request)
    return ''


if __name__ == '__main__':
    app.run('127.0.0.1', port=8087, debug=True)
