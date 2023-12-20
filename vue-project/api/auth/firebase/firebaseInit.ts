import { initializeApp } from 'firebase/app';

const firebaseConfig = {
  apiKey: "AIzaSyAGhWq3EqG909lXOacUE2BhNzsJvsEYwOw",
  authDomain: "vue-app-6e7de.firebaseapp.com",
  projectId: "vue-app-6e7de",
  storageBucket: "vue-app-6e7de.appspot.com",
  messagingSenderId: "626210091980",
  appId: "1:626210091980:web:09c11ea9a68cffa00d6369",
  measurementId: "G-D2KL9BQV8B"
};

export const initializeFireBase = () => {
  return initializeApp(firebaseConfig);
}

