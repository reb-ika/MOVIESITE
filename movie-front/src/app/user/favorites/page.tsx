'use client'

import { useEffect, useState } from 'react'

export default function FavoritesPage() {
  const [favorites, setFavorites] = useState([])
  const [toast, setToast] = useState(null)

  useEffect(() => {
    fetchFavorites()
  }, [])

  const fetchFavorites = async () => {
    try {
      const res = await fetch('http://localhost/MOVIESITE/api/favorites.php', {
        credentials: 'include'
      })
      if (!res.ok) throw new Error('Failed to fetch favorites')
      const data = await res.json()
      setFavorites(data)
    } catch (err) {
      console.error(err)
      showToast('Error loading favorites', 'error')
    }
  }

  const unfavorite = async (movieId) => {
    try {
      const res = await fetch(`http://localhost/MOVIESITE/api/favorite.php?movie_id=${movieId}`, {
        credentials: 'include'
      })
      const result = await res.json()
      if (result.status === 'success' && result.action === 'removed') {
        setFavorites(prev => prev.filter(movie => movie.id !== movieId))
        showToast('Removed from favorites', 'success')
      } else {
        showToast(result.message || 'Failed to unfavorite', 'error')
      }
    } catch (err) {
      console.error(err)
      showToast('Error removing favorite', 'error')
    }
  }

  const showToast = (message, type = 'info') => {
    setToast({ message, type })
    setTimeout(() => setToast(null), 3000)
  }

  return (
    <div className="bg-gray-50 min-h-screen p-6">
      {toast && (
        <div className={`fixed top-4 right-4 p-4 rounded text-white shadow-lg z-50 ${
          toast.type === 'error' ? 'bg-red-600' : 'bg-green-600'
        }`}>
          {toast.message}
        </div>
      )}

      <h1 className="text-3xl font-bold mb-6 text-center text-blue-800">My Favorite Movies</h1>

      {favorites.length === 0 ? (
        <p className="text-center text-gray-600">You haven’t added any favorites yet.</p>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          {favorites.map(movie => (
            <div key={movie.id} className="bg-white p-4 rounded-lg shadow border border-gray-200 hover:shadow-lg transition-all">
              <h2 className="text-xl font-semibold text-gray-800">{movie.title}</h2>
              <p className="text-sm text-gray-600">Genre: {movie.genre}</p>
              <p className="text-sm text-gray-600">Year: {movie.year}</p>
              <p className="text-gray-700 mt-2">{movie.description}</p>
              <button
                onClick={() => unfavorite(movie.id)}
                className="mt-4 px-4 py-2 rounded-full bg-red-500 hover:bg-red-600 text-white transition-colors"
              >
                Unfavorite ❤️
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
