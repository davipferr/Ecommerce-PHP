import { formatDate } from '@u/formatDate';

//usuário fez login -> dados salvos no localStorage
//usuário foi deslogado por vencimento de token ou manualmente -> excluir localStorage

type AddUserInStorage = {
  clientData?: any
  clientTokens?: any
}

export const addUserInStorage = async (obj: AddUserInStorage) => {
  localStorage.setItem('user', JSON.stringify({
    client: obj.clientData,
    clientTokens: obj.clientTokens,
  }));
}

export const getUserInStorage = async () => {
  const localStorageUserData = JSON.parse(localStorage.getItem('user') || '{}');

  return localStorageUserData
}

export const getAuthUser = async () => {
  const data = await getUserInStorage();

  if (!data || data.clientTokens.access_token < formatDate(Date.now())) {
    return false;
  }

  return data;
}