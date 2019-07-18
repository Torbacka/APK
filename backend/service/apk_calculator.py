from decimal import Decimal

from service.client.FirestoreClient import FirestoreClient
from service.client.SystembolagetClient import SystembolagetClient


def calculate_and_store():
    articles = SystembolagetClient().get_articles()
    calculated_articles = calculate(articles)
    # FirestoreClient().store(calculated_articles)
    pass


def calculate(articles):
    for article in articles:
        volume_cl = article['volume'] / Decimal('10')
        alcohol_by_volume = article['alcohol_by_volume']
        price = article['price']
        article['apk'] = (volume_cl * alcohol_by_volume)/price
    return ""


if __name__ == '__main__':
    calculate_and_store()
