import json

from flask import request, Flask, jsonify

from service.apk_calculator import calculate_and_store

app = Flask(__name__)


def recalculate():
    calculate_and_store("")
    pass


@app.route('/recalculate', methods=['GET'])
def local_recalculate():
    recalculate()
    return ''


if __name__ == '__main__':
    app.run('127.0.0.1', port=8087, debug=True)
