import { router } from "@inertiajs/vue3";
const navigateTo = (route_name, params) => {
  router.visit(route(route_name, params), {
    preserveState: true
  });
};
const navigateToEncounter = (summoner_id, encounter_id) => {
  navigateTo("summoner.encounter", {
    summoner_id,
    encounter_id
  });
};
const navigateToMatch = (summoner_id, summoner_match_id) => {
  navigateTo("summoner.match", {
    summoner_id,
    summoner_match_id
  });
};
export {
  navigateToMatch as a,
  navigateToEncounter as n
};
