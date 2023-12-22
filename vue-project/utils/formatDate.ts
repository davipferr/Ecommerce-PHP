//discrimineted unities

/* type AddTime = {
  hour?: number;
  minute?: number;
  second?: number;
  day?: number;
} */

export const formatDate = (time: number) => {

  /* const additionalTime = ref(0);

  if (addTime && Object.keys(addTime).length > 0) {

    additionalTime.value = 1000 
    * (addTime?.second ?? 60) // 1 minuto
    * (addTime?.minute ?? 60) // 1 hora
    * (addTime?.hour ?? 24) // 1 dia
    * (addTime?.day ?? 1) // 1 dia
  }
 */
  const date = new Date(time);

  return `
    ${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}|
    ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}
  `.replace(/\s+/g, '').replace(/\|/g, ' ');
}