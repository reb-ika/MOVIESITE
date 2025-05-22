'use client'
import { useEffect, useState } from 'react'

export default function MoviesPage() {
  const [movies, setMovies] = useState([])
  const [formData, setFormData] = useState({ title: '', genre: '', year: '', description: '' })
  const [editId, setEditId] = useState(null)
  const [search, setSearch] = useState('')
  const [toastMessage, setToastMessage] = useState(null)

  useEffect(() => {
    fetchMovies()
  }, [search])

  const fetchMovies = async () => {
    try {
      const res = await fetch(`http://localhost/MOVIESITE/api/movies.php?search=${search}`)
      if (!res.ok) throw new Error('Failed to fetch movies')
      const data = await res.json()
      setMovies(data)
    } catch (error) {
      console.error('Fetch movies error:', error)
      showToast('Error fetching movies', 'error')
    }
  }

  const handleDelete = async (id) => {
    try {
      const response = await fetch(`http://localhost/MOVIESITE/api/movies.php?id=${id}`, { 
        method: 'DELETE' 
      })
      if (!response.ok) throw new Error('Delete failed')
      showToast('Movie deleted successfully', 'success')
      fetchMovies()
    } catch (error) {
      console.error('Delete error:', error)
      showToast('Error deleting movie', 'error')
    }
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    try {
      const url = 'http://localhost/MOVIESITE/api/movies.php'
      const method = editId ? 'PUT' : 'POST'
      
      const response = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(editId ? { ...formData, id: editId } : formData)
      })
      
      if (!response.ok) throw new Error(editId ? 'Update failed' : 'Creation failed')

      showToast(`Movie ${editId ? 'updated' : 'added'} successfully`, 'success')
      setFormData({ title: '', genre: '', year: '', description: '' })
      setEditId(null)
      fetchMovies()
    } catch (error) {
      console.error('Submission error:', error)
      showToast(error.message || 'Operation failed', 'error')
    }
  }

  const showToast = (message, type = 'info') => {
    setToastMessage({ message, type })
    setTimeout(() => setToastMessage(null), 3000)
  }

  return (
    <div className="bg-white min-h-screen p-6">
      {/* Toast Notification */}
      {toastMessage && (
        <div className={`fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white ${
          toastMessage.type === 'error' ? 'bg-red-600' : 'bg-green-600'
        }`}>
          {toastMessage.message}
        </div>
      )}

      <div className="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
        <h1 className="text-2xl font-bold text-black">Movies Management</h1>
        <input
          type="text"
          placeholder="Search movies..."
          className="px-4 py-2 border border-gray-300 rounded text-black"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
      </div>

      <form onSubmit={handleSubmit} className="bg-white p-6 rounded-lg shadow-md mb-6 border border-gray-200">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input
            type="text"
            placeholder="Title"
            className="p-2 border border-gray-300 rounded text-black"
            value={formData.title}
            onChange={(e) => setFormData({ ...formData, title: e.target.value })}
            required
          />
          <input
            type="text"
            placeholder="Genre"
            className="p-2 border border-gray-300 rounded text-black"
            value={formData.genre}
            onChange={(e) => setFormData({ ...formData, genre: e.target.value })}
            required
          />
          <input
            type="number"
            placeholder="Year"
            className="p-2 border border-gray-300 rounded text-black"
            value={formData.year}
            onChange={(e) => setFormData({ ...formData, year: e.target.value })}
            required
          />
          <input
            type="text"
            placeholder="Description"
            className="p-2 border border-gray-300 rounded text-black"
            value={formData.description}
            onChange={(e) => setFormData({ ...formData, description: e.target.value })}
          />
        </div>
        <button 
          type="submit" 
          className="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
        >
          {editId ? 'Update Movie' : 'Add Movie'}
        </button>
      </form>

      <div className="rounded-lg shadow overflow-x-auto border border-gray-200">
        <table className="min-w-full">
          <thead className="bg-gray-50">
            <tr>
              {['Title', 'Genre', 'Year', 'Description', 'Actions'].map(header => (
                <th 
                  key={header} 
                  className="px-6 py-3 text-left text-black font-medium border-b border-gray-200"
                >
                  {header}
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {movies.map(movie => (
              <tr 
                key={movie.id} 
                className="border-b border-gray-200 hover:bg-gray-50 transition-colors"
              >
                <td className="px-6 py-4 text-black">{movie.title}</td>
                <td className="px-6 py-4 text-black">{movie.genre}</td>
                <td className="px-6 py-4 text-black">{movie.year}</td>
                <td className="px-6 py-4 text-black">{movie.description}</td>
                <td className="px-6 py-4 space-x-2">
                  <button
                    onClick={() => {
                      setFormData({
                        title: movie.title,
                        genre: movie.genre,
                        year: movie.year,
                        description: movie.description
                      })
                      setEditId(movie.id)
                    }}
                    className="text-blue-600 hover:text-blue-800 font-medium"
                  >
                    Edit
                  </button>
                  <button
                    onClick={() => handleDelete(movie.id)}
                    className="text-red-600 hover:text-red-800 font-medium"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}