export function isEmpty(text?: string|null|undefined): boolean {
    return text !== undefined && text !== null && text.length > 0;
}