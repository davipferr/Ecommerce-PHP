import api from '@u/api';

// [GET]
type GetClientByFireBaseData = {
  email: string
  access_token: string
}

export const getClientByFireBase = (data: GetClientByFireBaseData) => {
  return api({
    url: `/client/get-by-firebase/${data.email}/${data.access_token}`,
  });
} 


//[POST]
