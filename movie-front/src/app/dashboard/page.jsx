'use client'
import { useEffect, useState } from 'react'
import Link from 'next/link'
import { usePathname } from 'next/navigation'

export default function AdminLayout({ children }) {
  const [isSidebarOpen, setIsSidebarOpen] = useState(true)
  const pathname = usePathname()
  const [stats, setStats] = useState({
    movies: 0,
    users: 0,
    reviews: 0,
    genres: 0
  })

  useEffect(() => {
    async function fetchStats() {
      const res = await fetch('/api/stats')
      const data = await res.json()
      setStats(data)
    }
    fetchStats()
  }, [])

  const isActive = (path) => pathname === path

  return (
    <div className="min-h-screen bg-white">
      {/* Sidebar */}
      <aside className={`bg-gray-200 text-gray-800 w-64 fixed h-full transition-all ${isSidebarOpen ? 'ml-0' : '-ml-64'}`}>
        <div className="p-4 border-b border-gray-300">
          <h2 className="text-2xl font-bold">Admin Panel</h2>
        </div>
        
        <nav className="mt-4">
          <ul className="space-y-1">
            <li>
              <Link
                href="/dashboard"
                className={`block px-6 py-3 hover:bg-gray-300 ${isActive('/dashboard') ? 'bg-gray-300 font-medium' : ''}`}
              >
                Dashboard
              </Link>
            </li>
            <li>
              <Link
                href="/dashboard/movies"
                className={`block px-6 py-3 hover:bg-gray-300 ${isActive('/dashboard/movies') ? 'bg-gray-300 font-medium' : ''}`}
              >
                Movies
              </Link>
            </li>
            <li>
              <Link
                href="/dashboard/genres"
                className={`block px-6 py-3 hover:bg-gray-300 ${isActive('/dashboard/genres') ? 'bg-gray-300 font-medium' : ''}`}
              >
                Genres
              </Link>
            </li>
            <li>
              <Link
                href="/dashboard/users"
                className={`block px-6 py-3 hover:bg-gray-300 ${isActive('/dashboard/users') ? 'bg-gray-300 font-medium' : ''}`}
              >
                Users
              </Link>
            </li>
            <li>
              <Link
                href="/dashboard/reviews"
                className={`block px-6 py-3 hover:bg-gray-300 ${isActive('/dashboard/reviews') ? 'bg-gray-300 font-medium' : ''}`}
              >
                Reviews & Comments
              </Link>
            </li>
          </ul>
        </nav>
      </aside>

      {/* Main Content */}
      <main className={`transition-all ${isSidebarOpen ? 'ml-64' : 'ml-0'}`}>
        <div className="p-8">
          <button
            onClick={() => setIsSidebarOpen(!isSidebarOpen)}
            className="mb-4 p-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300"
          >
            ☰
          </button>

          {pathname === '/dashboard' ? (
            <div>
              <h1 className="text-2xl font-bold text-gray-800 mb-6">Dashboard Overview</h1>
              <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <StatCard title="Total Movies" value={stats.movies} color="bg-blue-100" />
                <StatCard title="Total Users" value={stats.users} color="bg-green-100" />
                <StatCard title="Total Reviews" value={stats.reviews} color="bg-yellow-100" />
                <StatCard title="Total Genres" value={stats.genres} color="bg-purple-100" />
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                  <h3 className="text-lg font-semibold mb-4">Recent Activity</h3>
                  {/* Add activity feed */}
                </div>
                <div className="bg-white p-6 rounded-lg shadow border border-gray-200">
                  <h3 className="text-lg font-semibold mb-4">Statistics Chart</h3>
                  {/* Add chart */}
                </div>
              </div>
            </div>
          ) : (
            children
          )}
        </div>
      </main>
    </div>
  )
}

function StatCard({ title, value, color }) {
  return (
    <div className={`${color} p-6 rounded-lg shadow border border-gray-200`}>
      <h3 className="text-gray-600 text-sm font-medium">{title}</h3>
      <p className="text-3xl font-bold text-gray-800 mt-2">{value}</p>
    </div>
  )
}