import api from '@u/api';
import { formatDate } from '@u/formatDate';

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
type AddRefereshTokenExpirationData = {
  clientId: number
  expiarationTime: number
}

export const addRefereshTokenExpiration = (data: AddRefereshTokenExpirationData) => {
  return api({
    url: `/client/add-refresh-token-expiration`,
    method: 'POST',
    data: {
      client_id: data.clientId,
      refresh_token_expiration_time: formatDate(data.expiarationTime),
    },
  });
}