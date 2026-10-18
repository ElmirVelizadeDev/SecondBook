export function decodeSellerPageData(encoded) {
    if (!encoded) return {};

    try {
        const bytes = Uint8Array.from(atob(encoded), (character) => character.charCodeAt(0));
        return JSON.parse(new TextDecoder().decode(bytes));
    } catch (error) {
        console.error('Unable to read Seller page data.', error);
        return {};
    }
}
