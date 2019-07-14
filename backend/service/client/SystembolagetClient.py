import requests
import untangle


class SystembolagetClient:
    _url = "https://www.systembolaget.se/api/assortment/products/xml"
    _session = requests.Session()

    def get_articles(self):
        print("hello")
        xml = self._session.get(self._url).content
        print("hello")
        return untangle.parse(str(xml, "utf-8"))
