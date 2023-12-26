import api from '@u/api';

//POST
export const refreshAccessToken = (clientId: number) => {
  return api({
    url: 'auth/refresh-access-token',
    method: 'POST',
    data: {
      client_id: clientId,
    },
  });
};

type CreateAccessToken = {
  id: number
  token: string
}

export const createAccessToken = (data: CreateAccessToken) => {
  return api({
    url: 'auth/create-access-token',
    method: 'POST',
    data: {
      client_id: data.id,
      new_access_token: data.token,
    }
  })
}

type CreateRefreshToken = {
  id: number
  token: string
}

export const createRefreshToken = (data: CreateRefreshToken) => {
  return api({
    url: 'auth/create-refresh-token',
    method: 'POST',
    data: {
      client_id: data.id,
      new_refresh_token: data.token,
    }
  })
}