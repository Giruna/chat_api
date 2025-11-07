export function errorHandling(response) {
  // Check the server's response
  if (response) {
    if (!response) {
      console.error('Unexpected error: no response object')

      return 'Unexpected error occurred.'
    }

    const data = response.data

    if (data && data.success === false) {
      return data.message || 'Request failed.'
    } else {
      return 'Server error: ' . response.status . response.statusText
    }
  }

  return 'An unknown error occurred.'
}
