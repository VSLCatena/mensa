import { DayOfWeek } from '../types/day-of-week.type';

export function getWeekDate(day: DayOfWeek, week: number): Date {
	const today = new Date();
	const distance = day - today.getDay() + 7 * week;
	today.setDate(today.getDate() + distance);
	today.setHours(0, 0, 0, 0);
	return today;
}
