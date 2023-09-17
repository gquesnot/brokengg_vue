const urlChampionHelper = (version, img_url) => `https://ddragon.leagueoflegends.com/cdn/${version}/img/champion/${img_url}`;
const urlItemHelper = (version, img_url) => `https://ddragon.leagueoflegends.com/cdn/${version}/img/item/${img_url}`;
const urlProfilIconHelper = (version, img_id) => `https://ddragon.leagueoflegends.com/cdn/${version}/img/profileicon/${img_id.toString()}.png`;
export {
  urlItemHelper as a,
  urlProfilIconHelper as b,
  urlChampionHelper as u
};
