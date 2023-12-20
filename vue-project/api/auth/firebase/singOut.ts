import { getAuth, signOut } from 'firebase/auth';

import { initializeFireBase } from './firebaseInit.ts';
initializeFireBase();

const auth = getAuth();

export function signOutUser() {
  return signOut(auth).then((res) => {
    console.log('res', res);
  
  }).catch((error) => {
    const errorObject ={
      errorCode: error.code,
      errorMessage: error.messages,
      errorStack: error.Stack,
    }

    return errorObject;
  })
}
