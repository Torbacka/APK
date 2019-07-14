from service.client.FirestoreClient import FirestoreClient
from service.client.SystembolagetClient import SystembolagetClient


def calculate_and_store(articles):
    articles = SystembolagetClient().get_articles()
    calculated_articles = calculate(articles)
    FirestoreClient().store(calculated_articles)
    pass


def calculate(articles):
    return ""
