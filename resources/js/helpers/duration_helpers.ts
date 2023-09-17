

export const getDurationMinutes = (duration:string | null| undefined) : number =>{
    if(!duration) return 0;
    let date_ = new Date("2000-01-01 " + duration)
    return date_.getHours() * 60 + date_.getMinutes();
}
