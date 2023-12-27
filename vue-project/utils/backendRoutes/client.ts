import api from '@u/api';

// [GET]
export const getClientByFireBase = (email: string) => {
  return api({
    url: `/client/get-by-firebase/${email}`,
  });
} 


//[POST]
