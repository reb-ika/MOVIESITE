"use client";

import { useEffect, useState } from "react";
import Header from "../components/Header";
import Footer from "../components/Footer";

export default function LandingPage() {
  const [heroMovies, setHeroMovies] = useState([]);
  const [movies, setMovies] = useState([]);

  // Fetch movies from TMDB API
  useEffect(() => {
    async function fetchMovies() {
      try {
        const response = await fetch(
          `https://api.themoviedb.org/3/movie/popular?api_key=12363dab001ab56b7c61ab2dd890eec1&language=en-US&page=1`
        );
        const data = await response.json();
        setMovies(data.results.slice(3, 9)); // Movies for the Popular Movies section
        setHeroMovies(data.results.slice(0, 3)); // Top 3 movies for Hero Section
      } catch (error) {
        console.error("Failed to fetch movies:", error);
      }
    }
    fetchMovies();
  }, []);

  const testimonials = [
    {
      name: "Sophia K.",
      feedback: "MovieZone changed the way I discover new films. It’s my go-to app!",
    },
    {
      name: "Liam B.",
      feedback: "The reviews and trailers help me choose what to watch every weekend.",
    },
    {
      name: "Ava T.",
      feedback: "I love the clean interface and personalized recommendations!",
    },
  ];

  // Create Hero Section Background
  const heroBackgroundStyle = heroMovies
    .map((movie) => `url(https://image.tmdb.org/t/p/original${movie.backdrop_path})`)
    .join(", ");

  return (
    <main className="min-h-screen bg-white text-gray-900">
      <Header />

      {/* Scroll to Top Button */}
      <a
        href="#top"
        id="scrollTop"
        className="fixed bottom-6 right-6 bg-red-600 text-white p-3 rounded-full shadow-lg hover:bg-red-700 transition"
      >
        ⬆
      </a>

      {/* Hero Section */}
      <section
        id="top"
        className="relative text-white text-center py-64 px-6 bg-cover bg-center"
        style={{
          backgroundImage: `${heroBackgroundStyle}`,
        }}
      >
        <div className="absolute inset-0 bg-black bg-opacity-50"></div>
        <div className="relative">
          <h1 className="text-5xl md:text-6xl font-bold mb-6 animate-fadeIn">
            Welcome to MovieZone 🎬
          </h1>
          <p className="text-xl text-gray-300 mb-8 max-w-2xl mx-auto animate-fadeIn delay-100">
            Discover, explore, and review your favorite movies in one place.
          </p>
          <div className="flex justify-center gap-6 animate-fadeIn delay-200">
            <a
              href="/login"
              className="bg-white text-gray-900 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition"
            >
              Login
            </a>
            <a
              href="/signup"
              className="border border-white px-6 py-3 rounded-xl font-semibold hover:bg-white hover:text-gray-900 transition"
            >
              Sign Up
            </a>
          </div>
        </div>
      </section>

      {/* Features */}
      <section className="py-20 px-6 text-center">
        <h2 className="text-3xl font-bold mb-8">Why MovieZone?</h2>
        <div className="grid md:grid-cols-3 gap-12">
          <FeatureCard
            title="🎥 Watch Trailers"
            desc="Preview the latest blockbusters before watching."
          />
          <FeatureCard
            title="⭐ Rate & Review"
            desc="Share opinions and read verified reviews."
          />
          <FeatureCard
            title="🎯 Personalized Feed"
            desc="Smart recommendations based on your tastes."
          />
        </div>
      </section>

      {/* Popular Movies */}
      <section className="bg-gray-100 py-20 px-6 text-center">
        <h2 className="text-3xl font-bold mb-10">Top Picks</h2>
        <div className="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          {movies.length > 0 ? (
            movies.map((movie, i) => (
              <div
                key={i}
                className="bg-white rounded-xl shadow-lg overflow-hidden"
              >
                <img
                  src={`https://image.tmdb.org/t/p/w500${movie.poster_path}`}
                  alt={movie.title}
                  className="w-full h-80 object-cover"
                />
                <div className="p-6">
                  <h3 className="text-xl font-semibold mb-2">{movie.title}</h3>
                  <p className="text-gray-500">({movie.release_date.split("-")[0]})</p>
                  <p className="mt-2 text-yellow-500 font-bold">
                    ⭐ {movie.vote_average}
                  </p>
                </div>
              </div>
            ))
          ) : (
            <p>Loading movies...</p>
          )}
        </div>
      </section>

      {/* Testimonials */}
      <section className="py-20 px-6 text-center bg-white">
        <h2 className="text-3xl font-bold mb-10">What Users Say</h2>
        <div className="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">
          {testimonials.map((testi, i) => (
            <div key={i} className="bg-gray-100 rounded-xl p-6 shadow">
              <p className="text-gray-700 italic mb-4">“{testi.feedback}”</p>
              <p className="text-gray-900 font-semibold">- {testi.name}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Footer */}
      <Footer />
    </main>
  );
}

function FeatureCard({ title, desc }) {
  return (
    <div>
      <h3 className="text-xl font-semibold mb-2">{title}</h3>
      <p className="text-gray-600">{desc}</p>
    </div>
  );
}