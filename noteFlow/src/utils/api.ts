const API_BASE = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

interface ApiResponse<T = unknown> {
  data?: T
  error?: string
}

function getToken(): string | null {
  return localStorage.getItem('auth_token')
}

export function setToken(token: string): void {
  localStorage.setItem('auth_token', token)
}

export function removeToken(): void {
  localStorage.removeItem('auth_token')
}

export function isAuthenticated(): boolean {
  return !!getToken()
}

async function request<T>(
  endpoint: string,
  options: RequestInit = {},
): Promise<ApiResponse<T>> {
  const token = getToken()

  const headers: Record<string, string> = {
    'Accept': 'application/json',
    ...(options.headers as Record<string, string>),
  }

  if (token) {
    headers['Authorization'] = `Bearer ${token}`
  }

  if (options.body && typeof options.body === 'string') {
    headers['Content-Type'] = 'application/json'
  }

  try {
    const res = await fetch(`${API_BASE}${endpoint}`, {
      ...options,
      headers,
    })

    const data = await res.json()

    if (!res.ok) {
      const message =
        data.message || data.error || Object.values(data.errors || {}).flat().join(', ')
      return { error: message }
    }

    return { data }
  } catch (err) {
    return { error: err instanceof Error ? err.message : 'Network error' }
  }
}

export const api = {
  register(name: string, email: string, password: string, passwordConfirmation: string) {
    return request<{ user: { id: number; name: string; email: string }; token: string }>(
      '/auth/register',
      {
        method: 'POST',
        body: JSON.stringify({
          name,
          email,
          password,
          password_confirmation: passwordConfirmation,
        }),
      },
    )
  },

  login(email: string, password: string) {
    return request<{ user: { id: number; name: string; email: string }; token: string }>(
      '/auth/login',
      { method: 'POST', body: JSON.stringify({ email, password }) },
    )
  },

  logout() {
    return request<{ message: string }>('/auth/logout', { method: 'POST' })
  },

  user() {
    return request<{ id: number; name: string; email: string }>('/user')
  },
}
