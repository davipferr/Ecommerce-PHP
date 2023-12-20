import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword } from 'firebase/auth';

import { initializeFireBase } from './firebaseInit.ts';
initializeFireBase();

const auth = getAuth();

//Promisse e callback
export function createUser(email: string, password: string) {
  return createUserWithEmailAndPassword(auth, email, password)
  .then((userCredential) => {
    const user = userCredential.user;
    return user;
  })
  .catch((error) => {
    const errorObject = {
      errorMessage: error.message,
      errorCode: error.code,
      errorStack: error.stack,
    }

    return errorObject;
  });
}

export function singInUser(email: string, password: string) {
  return signInWithEmailAndPassword(auth, email, password)
  .then((userCredential) => {
    const user = userCredential.user;
    return user;
  })
  .catch((error) => {
    const errorObject = {
      errorMessage: error.message,
      errorCode: error.code,
      errorStack: error.stack,
    }

    return errorObject;
  });
}