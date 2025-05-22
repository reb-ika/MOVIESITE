'use client'
import { useEffect, useState } from 'react'

export default function ReviewsPage() {
  const [reviews, setReviews] = useState([])
  const [formData, setFormData] = useState({ 
    user_id: '', 
    movie_id: '', 
    rating: '', 
    comment: '' 
  })
  const [editId, setEditId] = useState(null)
  const [search, setSearch] = useState('')
  const [toastMessage, setToastMessage] = useState(null)

  useEffect(() => {
    fetchReviews()
  }, [search])

  const fetchReviews = async () => {
    try {
      const res = await fetch(`http://localhost/MOVIESITE/api/review.php?search=${search}`)
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`)
      const data = await res.json()
      if (data.error) throw new Error(data.error)
      setReviews(data)
    } catch (error) {
      console.error('Fetch reviews error:', error)
      showToast(error.message || 'Error fetching reviews', 'error')
    }
  }

  const handleDelete = async (id) => {
    try {
      const response = await fetch(`http://localhost/MOVIESITE/api/review.php?id=${id}`, { 
        method: 'DELETE' 
      })
      const result = await response.json()
      if (!response.ok || result.error) throw new Error(result.error || 'Delete failed')
      showToast('Review deleted successfully', 'success')
      fetchReviews()
    } catch (error) {
      console.error('Delete error:', error)
      showToast(error.message || 'Error deleting review', 'error')
    }
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    try {
      const url = 'http://localhost/MOVIESITE/api/review.php'
      const method = editId ? 'PUT' : 'POST'
      
      const response = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(editId ? { ...formData, id: editId } : formData)
      })
      
      const result = await response.json()
      if (!response.ok || result.error) {
        throw new Error(result.error || (editId ? 'Update failed' : 'Creation failed'))
      }

      showToast(`Review ${editId ? 'updated' : 'added'} successfully`, 'success')
      setFormData({ user_id: '', movie_id: '', rating: '', comment: '' })
      setEditId(null)
      fetchReviews()
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
          toastMessage.type === 'error' ? 'bg-red-600' : 'bg-blue-600'
        }`}>
          {toastMessage.message}
        </div>
      )}

      <div className="flex justify-between items-center mb-6 border-b border-blue-200 pb-4">
        <h1 className="text-2xl font-bold text-blue-800">Reviews Management</h1>
        <input
          type="text"
          placeholder="Search reviews..."
          className="px-4 py-2 border border-blue-300 rounded-lg text-blue-900 focus:ring-2 focus:ring-blue-500"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
      </div>

      <form onSubmit={handleSubmit} className="bg-blue-50 p-6 rounded-xl shadow-sm mb-6 border border-blue-200">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input
            type="number"
            placeholder="User ID"
            className="p-2 border border-blue-300 rounded-lg text-blue-900 focus:ring-2 focus:ring-blue-500"
            value={formData.user_id}
            onChange={(e) => setFormData({ ...formData, user_id: e.target.value })}
            required
          />
          <input
            type="number"
            placeholder="Movie ID"
            className="p-2 border border-blue-300 rounded-lg text-blue-900 focus:ring-2 focus:ring-blue-500"
            value={formData.movie_id}
            onChange={(e) => setFormData({ ...formData, movie_id: e.target.value })}
            required
          />
          <select
            value={formData.rating}
            onChange={(e) => setFormData({ ...formData, rating: e.target.value })}
            className="p-2 border border-blue-300 rounded-lg text-blue-900 focus:ring-2 focus:ring-blue-500"
            required
          >
            <option value="">Select Rating</option>
            {[1, 2, 3, 4, 5].map(num => (
              <option key={num} value={num}>{num} Star{num !== 1 ? 's' : ''}</option>
            ))}
          </select>
          <input
            type="text"
            placeholder="Comment"
            className="p-2 border border-blue-300 rounded-lg text-blue-900 focus:ring-2 focus:ring-blue-500"
            value={formData.comment}
            onChange={(e) => setFormData({ ...formData, comment: e.target.value })}
            required
          />
        </div>
        <button 
          type="submit" 
          className="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold"
        >
          {editId ? 'Update Review' : 'Add Review'}
        </button>
      </form>

      <div className="rounded-xl shadow-sm overflow-x-auto border border-blue-200">
        <table className="min-w-full">
          <thead className="bg-blue-600 text-white">
            <tr>
              {['User', 'Movie', 'Rating', 'Comment', 'Date', 'Actions'].map(header => (
                <th 
                  key={header} 
                  className="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider"
                >
                  {header}
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {reviews.map(review => (
              <tr 
                key={review.id} 
                className="border-b border-blue-100 hover:bg-blue-50 transition-colors"
              >
                <td className="px-6 py-4 text-blue-900 font-medium">{review.username}</td>
                <td className="px-6 py-4 text-blue-800">{review.movie_title}</td>
                <td className="px-6 py-4 text-blue-800">
                  <div className="flex items-center">
                    {Array(5).fill().map((_, i) => (
                      <svg
                        key={i}
                        className={`w-4 h-4 ${i < review.rating ? 'text-yellow-400' : 'text-gray-300'}`}
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    ))}
                  </div>
                </td>
                <td className="px-6 py-4 text-blue-700 max-w-xs truncate">{review.comment}</td>
                <td className="px-6 py-4 text-blue-700">
                  {new Date(review.created_at).toLocaleDateString()}
                </td>
                <td className="px-6 py-4 space-x-3">
                  <button
                    onClick={() => {
                      setFormData({
                        user_id: review.user_id,
                        movie_id: review.movie_id,
                        rating: review.rating,
                        comment: review.comment
                      })
                      setEditId(review.id)
                    }}
                    className="text-blue-600 hover:text-blue-800 font-medium px-3 py-1 rounded-md hover:bg-blue-100"
                  >
                    Edit
                  </button>
                  <button
                    onClick={() => handleDelete(review.id)}
                    className="text-red-600 hover:text-red-800 font-medium px-3 py-1 rounded-md hover:bg-red-100"
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