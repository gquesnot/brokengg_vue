export const withoutTagLine = (summoner_name: string) => {
    return summoner_name.split("#")[0];
}


export const tagLineOnly = (summoner_name: string) => {
    return summoner_name.split("#")[1];
}
