export function saveLoginData(data) {
  // Save login data to localStorage
  localStorage.setItem('token', data.token)
  localStorage.setItem('userid', data.user.id)
  localStorage.setItem('username', data.user.name)

  window.dispatchEvent(new Event('auth-changed'))
}

export function removeLoginData() {
  // Remove login data from LocalStorage
  localStorage.removeItem('token')
  localStorage.removeItem('userid')
  localStorage.removeItem('username')

  window.dispatchEvent(new Event('auth-changed'))
}
