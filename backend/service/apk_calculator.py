from service.client.FirestoreClient import FirestoreClient
from service.client.SystembolagetClient import SystembolagetClient


def calculate_and_store():
    articles = SystembolagetClient().get_articles()
    calculated_articles = calculate(articles)
    FirestoreClient().store(calculated_articles)
    pass


def calculate(articles):
    for article in articles:
        article['apk'] = (article['volume'] * article['alcohol_by_volume'])/article['price']
    return articles


if __name__ == '__main__':
    calculate_and_store()
