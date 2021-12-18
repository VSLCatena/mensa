export default function MapRequestDate(date: Date): number {
    return date.getTime() / 1000;
}