from google.cloud import firestore


class FirestoreClient:
    _database = firestore.Client()

    def store(self, articles):
        batch = self._database.batch()
        for i, article in enumerate(articles):
            document = self._database.collection("apk").document(article['number'])
            batch.set(document, article)
            if i % 499 == 0:
                batch.commit()
                print(500)

    def get(self):
        docs = self._database.collection("apk").stream()
        i = 0
        for doc in docs:
            i += 1
        print(i)


if __name__ == '__main__':
    FirestoreClient().get()
