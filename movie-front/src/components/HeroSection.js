const HeroSection = () => {
  return (
    <section
      className="relative bg-cover bg-center h-screen"
      style={{
        backgroundImage: "url('/cinima.jpg')", 
      }}
    >
      <div className="absolute inset-0 bg-black bg-opacity-50"></div>
      <div className="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
        <h1 className="text-4xl md:text-6xl font-bold mb-6 text-white">
          Welcome to MovieZone
        </h1>
        <p className="text-lg md:text-xl mb-8 text-gray-300">
          Discover the latest movies, read reviews, and share your thoughts!
        </p>
        <div className="space-x-4">
          <a
            href="/login"
            className="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-bold transition"
          >
            Login
          </a>
          <a
            href="/signup"
            className="px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg text-white font-bold transition"
          >
            Sign Up
          </a>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
