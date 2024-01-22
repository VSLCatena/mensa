import { getWeekDate } from './date.helper';
import { DayOfWeek } from '../types/day-of-week.type';

describe('DateHelper', () => {
    let today = new Date();

    beforeEach(async () => {
        today = new Date();
    });

    it('should give correct day', () => {
        // Arrange
        const weekDay = DayOfWeek.Monday;
        const week = 0; // Current week

        // Act
        const result = getWeekDate(weekDay, week);

        // Assert
        expect(result.getDay()).toBe(weekDay);
    });

    it('should give correct time', () => {
        // Arrange
        const weekDay = DayOfWeek.Monday;
        const week = 0; // Current week

        // Act
        const result = getWeekDate(weekDay, week);

        // Assert
        expect(result.getHours()).toBe(0);
    });
});
