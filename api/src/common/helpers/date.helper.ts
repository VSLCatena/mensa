import { DayOfWeek } from '../types/day-of-week.type';

export function getWeekDate(day: DayOfWeek, week: number): Date {
	const today = new Date();
	let distance = day - today.getDay() + 7 * week;
	if (distance > 0 && week === 0) {
		distance -= 7;
	}
	today.setDate(today.getDate() + distance);
	today.setHours(0, 0, 0, 0);
	return today;
}
