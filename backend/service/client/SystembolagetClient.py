import requests
import untangle


class SystembolagetClient:
    _url = "https://www.systembolaget.se/api/assortment/products/xml"
    _session = requests.Session()

    def get_articles(self):
        xml = self._session.get(self._url).content
        return untangle.parse(str(xml, "utf-8"))
