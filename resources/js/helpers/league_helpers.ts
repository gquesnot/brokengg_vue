import {SummonerMatchInterface} from "@/types/summoner_match";


enum LeagueTierType {
    IRON = "IRON",
    BRONZE = "BRONZE",
    SILVER = "SILVER",
    GOLD = "GOLD",
    PLATINUM = "PLATINUM",
    EMERALD = "EMERALD",
    DIAMOND = "DIAMOND",
    MASTER = "MASTER",
    GRANDMASTER = "GRANDMASTER",
    CHALLENGER = "CHALLENGER"
}

enum LeagueRankType {
    I = "I",
    II = "II",
    III = "III",
    IV = "IV"
}


export const getLeagueRankFromInt = (league_rank: number): LeagueRankType => {
    if (league_rank === 3) return LeagueRankType.I;
    if (league_rank === 2) return LeagueRankType.II;
    if (league_rank === 1) return LeagueRankType.III;
    if (league_rank === 0) return LeagueRankType.IV;
    return LeagueRankType.I;
}


export const getAvgRankString = (summoner_matches: SummonerMatchInterface[]): string => {
    let total_team_position = 0;
    let total_team_count = 0;

    summoner_matches.forEach((match: SummonerMatchInterface) => {
        if (match.summoner.solo_q) {
            total_team_position += match.summoner.solo_q.overall_position;
            total_team_count++;
        }
    })
    if (total_team_position === 0) {
        return "Undefined";
    }
    let avg_position = Math.ceil(total_team_position / (total_team_count ? total_team_count : 1));
    return getTierRankString(avg_position);
}

export const getTierRankString = (avg_overall_position: number): string => {
    let tier: LeagueTierType;
    let rank: LeagueRankType;
    if (avg_overall_position >= 30) {
        tier = LeagueTierType.CHALLENGER;
        rank = LeagueRankType.I;
    } else if (avg_overall_position >= 29) {
        tier = LeagueTierType.GRANDMASTER;
        rank = LeagueRankType.I;
    } else if (avg_overall_position >= 28) {
        tier = LeagueTierType.MASTER;
        rank = LeagueRankType.I;
    } else {
        // Calculate tier and rank for other levels
        if (avg_overall_position < 4) {
            tier = LeagueTierType.IRON;
            rank = getLeagueRankFromInt(avg_overall_position)
        } else if (avg_overall_position < 8) {
            tier = LeagueTierType.BRONZE;
            rank = getLeagueRankFromInt(avg_overall_position - 4)
        } else if (avg_overall_position < 12) {
            tier = LeagueTierType.SILVER;
            rank = getLeagueRankFromInt(avg_overall_position - 8)
        } else if (avg_overall_position < 16) {
            tier = LeagueTierType.GOLD;
            rank = getLeagueRankFromInt(avg_overall_position - 12)
        } else if (avg_overall_position < 20) {
            tier = LeagueTierType.PLATINUM;
            rank = getLeagueRankFromInt(avg_overall_position - 16)
        } else if (avg_overall_position < 24) {
            tier = LeagueTierType.EMERALD;
            rank = getLeagueRankFromInt(avg_overall_position - 20)
        } else {
            tier = LeagueTierType.DIAMOND;
            rank = getLeagueRankFromInt(avg_overall_position - 24)
        }
    }
    return `${tier} ${rank}`;
}
