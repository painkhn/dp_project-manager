// resources/js/utils/dateValidation.ts

export const isValidDate = (dateString: string): boolean => {
    const datePattern = /^\d{4}-\d{2}-\d{2}$/;
    if (!datePattern.test(dateString)) {
        return false;
    }

    const [year, month, day] = dateString.split('-').map(Number);
    const date = new Date(year, month - 1, day);

    return (
        date.getFullYear() === year &&
        date.getMonth() === month - 1 &&
        date.getDate() === day
    );
};