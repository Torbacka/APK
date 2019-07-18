from datetime import datetime
from decimal import Decimal

import requests
import xmltodict


def parse(articles):
    parsed_articles = []
    for article in articles['artiklar']['artikel']:
        parsed_articles.append({
            'number': article['nr'],
            'article_id': article['Artikelid'],
            'part_number': article['Varnummer'],
            'name': article['Namn'],
            'name2': article['Namn2'],
            'price': float(article['Prisinklmoms']),
            'volume': float(article['Volymiml']),
            'price_per_liter': float(article['PrisPerLiter']),
            'sale_start': datetime.strptime(article['Saljstart'], "%Y-%m-%d"),
            'discontinued': article['Utgått'] == 1 if True else False,
            'part_group': article['Varugrupp'],
            'type': article['Typ'],
            'style': article['Stil'],
            'packaging': article['Forpackning'],
            'seal': article['Forslutning'],
            'origin': article['Ursprung'],
            'country_of_origin': article['Ursprunglandnamn'],
            'producer': article.get('Producent'),
            'supplier': article.get('Leverantor'),
            'vintage': article['Argang'],
            'tested_vintage': article['Provadargang'],
            'alcohol_by_volume': float(article['Alkoholhalt'][:-1]) / float(100),
            'assortment': article['Sortiment'],
            'assortment_text': article['SortimentText'],
            'organic': article['Ekologisk'] == 1 if True else False,
            'ethical': article['Etiskt'] == 1 if True else False,
            'kosher': article['Koscher'] == 1 if True else False,
            'commodity_description': article.get('RavarorBeskrivning'),

        })
    return parsed_articles


class SystembolagetClient:
    _url = "https://www.systembolaget.se/api/assortment/products/xml"
    _session = requests.Session()

    def get_articles(self):
        xml = self._session.get(self._url).content
        return parse(xmltodict.parse(str(xml, "utf-8")))
