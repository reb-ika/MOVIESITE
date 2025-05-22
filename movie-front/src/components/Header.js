const Header = () => {
  return (
    <header className="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white shadow-md">
      <div className="container mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between">
        <h1 className="text-3xl font-extrabold tracking-wide text-yellow-400 mb-2 sm:mb-0 hover:scale-105 transition-transform duration-300">
          🎬 MovieZone
        </h1>

        <nav>
          <ul className="flex flex-wrap items-center justify-center gap-4 text-sm sm:text-base">
            <li>
              <a href="/user" className="hover:text-yellow-400 transition-colors duration-200">
                Home
              </a>
            </li>
            <li>
              <a href="/user/movies" className="hover:text-yellow-400 transition-colors duration-200">
                Movies
              </a>
            </li>
            <li>
              <a href="/user/favorites" className="hover:text-yellow-400 transition-colors duration-200">
                Favorites
              </a>
            </li>
            <li>
              <a
                href="/login"
                className="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full transition duration-300"
              >
                Logout
              </a>
            </li>
            <li>
              <a
                href="/signup"
                className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full transition duration-300"
              >
                Sign Up
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </header>
  )
}

export default Header
