import { useRouter } from 'vue-router';

const router = useRouter();

//usuário fez login -> dados salvos no localStorage
  //usuário foi deslogado por vencimento de token ou manualmente -> excluir localStorage

const user = JSON.parse(localStorage.getItem('user') || '{}');

if (!user.email || !user.stsTokenManager.accessToken) {

  console.log('Usuário deslogado');

  router.push({path: '/login'});
} else {
  console.log('Usuário logado');
}