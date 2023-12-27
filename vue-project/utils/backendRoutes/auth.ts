import api from '@u/api';

//POST
export const validateRefreshToken = (expirationTime:  Date | null ) => {
  return api({
    url: 'auth/validate-refresh-token',
    method: 'POST',
    data: {
      refresh_token_expiration_time: expirationTime,
    },
  });
}

export const refreshAccessToken = (clientId: number) => {
  return api({
    url: 'auth/refresh-access-token',
    method: 'POST',
    data: {
      client_id: clientId,
    },
  });
}

type CreateAccessToken = {
  client_id: number
  new_acces_token: string
}

export const createAccessToken = (data: CreateAccessToken) => {
  return api({
    url: 'auth/create-access-token',
    method: 'POST',
    data: {
      client_id: data.client_id,
      new_access_token: data.new_acces_token,
    },
  });
}

type CreateRefreshToken = {
  client_id: number
  new_acces_token: string
}

export const addRefreshToken = (data: CreateRefreshToken) => {
  return api({
    url: 'auth/add-refresh-token',
    method: 'POST',
    data: {
      client_id: data.client_id,
      new_refresh_token: data.new_acces_token,
    },
  });
}

export const deleteAllClientTokens = (clientId: number) => {
  return api({
    url: 'auth/delete-all-tokens',
    method: 'POST',
    data: {
      client_id: clientId,
    },
  });
}