/**
 * Sends a POST request to the specified URL with the provided form data.
 * Attempts to parse the response as JSON, and handles errors appropriately.
 * 
 * @param {string} url - The URL to send the POST request to.
 * @param {FormData|Object} formData - The data to be sent in the body of the request.
 * @returns {Promise<Object>} - A promise that resolves to a response object. 
 * If the request is successful and the server returns valid JSON, the JSON data is returned.
 * If the request fails, or if the server returns invalid JSON, an error object with a `success: false` property is returned.
 */
export async function fetchPOST(url, formData) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        // Lire le corps de la réponse comme du texte
        const text = await response.text();

        // Tenter de parser le texte comme JSON
        try {
            const data = JSON.parse(text);

            // Si 'success' est false dans la réponse JSON, retourner un message d'erreur
            if (!data.success) {
                return { success: false, message: data.message || "Unknown error" };
            }

            // Retourner l'objet JSON si tout est correct
            return data;

        } catch (jsonError) {
            // Si l'analyse JSON échoue, retourner le texte brut
            return { success: false, message: text };
        }
    } catch (networkError) {
        // Gérer les erreurs réseau ou autres types d'exceptions
        return { success: false, message: networkError.message };
    }
}
