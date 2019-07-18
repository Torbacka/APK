from concurrent.futures.thread import ThreadPoolExecutor

from google.cloud import firestore


class FirestoreClient:
    _database = firestore.Client()

    def store(self, articles):
        batch = self._database.batch()
        with ThreadPoolExecutor(max_workers=8) as executor:
            for i, article in enumerate( articles):
                document = self._database.collection("apk").document()
                batch.set(document, article)
                if i % 499 == 0:
                    executor.submit(batch.commit())
                    print(500)
            executor.shutdown(True)
