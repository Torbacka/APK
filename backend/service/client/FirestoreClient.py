from google.cloud import firestore


class FirestoreClient:
    _database = firestore.Client()

    def store(self, articles):
        document = self._database.collection("apk")
        for article in articles:
            document.add(article)
