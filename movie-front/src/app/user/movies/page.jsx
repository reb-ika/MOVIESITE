'use client'

import React, { useEffect, useState } from 'react';
import Header from "../../../components/Header";
import Footer from "../../../components/Footer";
import { useRouter } from 'next/navigation';

const API_BASE = 'http://localhost/MOVIESITE/api'; // Your backend URL

function MovieCard({ movie, onToggleLike, onToggleFavorite, liked, favorited, reviews, onClick }) {
  const posterUrl = movie.poster_path
    ? `https://image.tmdb.org/t/p/w342${movie.poster_path}`
    : `https://via.placeholder.com/300x450?text=${encodeURIComponent(movie.title)}`;

  return (
    <div
      onClick={onClick}
      style={{
        backgroundColor: 'white',
        color: 'black',
        borderRadius: 12,
        padding: 20,
        boxShadow: '0 6px 15px rgba(0,0,0,0.15)',
        width: '100%',
        cursor: 'pointer',
        display: 'flex',
        flexDirection: 'column',
        transition: 'transform 0.2s',
      }}
      onMouseEnter={e => (e.currentTarget.style.transform = 'scale(1.03)')}
      onMouseLeave={e => (e.currentTarget.style.transform = 'scale(1)')}
    >
      <img
        src={posterUrl}
        alt={movie.title}
        style={{
          width: '100%',
          height: 350,
          objectFit: 'cover',
          borderRadius: 12,
          marginBottom: 16,
          boxShadow: '0 4px 10px rgba(0,0,0,0.1)',
        }}
      />
      <h3 style={{ margin: 0, marginBottom: 8, fontSize: 20 }}>{movie.title} ({movie.year || ''})</h3>
      <p style={{ fontSize: 14, color: '#333', marginBottom: 12, minHeight: 48 }}>
        {movie.genre || 'Unknown Genre'}
      </p>
      <div style={{ marginBottom: 16, fontSize: 14, minHeight: 80, color: '#555', overflow: 'hidden' }}>
        {movie.description || 'No description available.'}
      </div>

      <div style={{ display: 'flex', gap: 12, marginBottom: 16 }}>
        <button
          onClick={e => {
            e.stopPropagation();
            onToggleLike(movie.id);
          }}
          style={{
            flex: 1,
            backgroundColor: liked ? '#e50914' : '#ccc',
            border: 'none',
            color: 'white',
            padding: '10px',
            borderRadius: 6,
            fontWeight: '600',
            cursor: 'pointer',
            transition: 'background-color 0.3s',
          }}
          onMouseEnter={e => (e.currentTarget.style.backgroundColor = liked ? '#b0060e' : '#aaa')}
          onMouseLeave={e => (e.currentTarget.style.backgroundColor = liked ? '#e50914' : '#ccc')}
        >
          {liked ? '💖 Unlike' : '🤍 Like'}
        </button>
        <button
          onClick={e => {
            e.stopPropagation();
            onToggleFavorite(movie.id);
          }}
          style={{
            flex: 1,
            backgroundColor: favorited ? '#f5c518' : '#ccc',
            border: 'none',
            color: favorited ? '#222' : 'black',
            padding: '10px',
            borderRadius: 6,
            fontWeight: '600',
            cursor: 'pointer',
            transition: 'background-color 0.3s',
          }}
          onMouseEnter={e => (e.currentTarget.style.backgroundColor = favorited ? '#d4a80b' : '#aaa')}
          onMouseLeave={e => (e.currentTarget.style.backgroundColor = favorited ? '#f5c518' : '#ccc')}
        >
          {favorited ? '⭐ Remove' : '☆ Favorite'}
        </button>
      </div>

      <h4 style={{ margin: '0 0 8px 0' }}>Reviews</h4>
      {reviews.length === 0 ? (
        <p style={{ fontStyle: 'italic', color: '#999', fontSize: 12 }}>No reviews yet.</p>
      ) : (
        <div style={{ maxHeight: 90, overflowY: 'auto', fontSize: 12, color: '#444' }}>
          {reviews.map(r => (
            <div key={r.id} style={{ marginBottom: 6, borderBottom: '1px solid #ddd', paddingBottom: 6 }}>
              <b>{r.username}</b> rated <b>{r.rating}/5</b>
              <p style={{ margin: '4px 0 0 0' }}>{r.comment}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default function MoviesUserView() {
  const [movies, setMovies] = useState([]);
  const [likes, setLikes] = useState(new Set());
  const [favorites, setFavorites] = useState(new Set());
  const [reviews, setReviews] = useState({});
  const router = useRouter();

  useEffect(() => {
    fetch(`${API_BASE}/movies.php`)
      .then(res => res.json())
      .then(data => setMovies(data))
      .catch(console.error);

    fetch(`${API_BASE}/reviews.php`)
      .then(res => res.json())
      .then(data => {
        const byMovie = {};
        data.forEach(r => {
          if (!byMovie[r.movie_id]) byMovie[r.movie_id] = [];
          byMovie[r.movie_id].push(r);
        });
        setReviews(byMovie);
      })
      .catch(console.error);

    // Temporary initial liked/favorites for demo
    setLikes(new Set([1, 3]));
    setFavorites(new Set([2]));
  }, []);

  const toggleLike = movieId => {
    const updatedLikes = new Set(likes);
    if (likes.has(movieId)) {
      updatedLikes.delete(movieId);
      fetch(`${API_BASE}/likes.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ movie_id: movieId }),
      });
    } else {
      updatedLikes.add(movieId);
      fetch(`${API_BASE}/likes.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ movie_id: movieId }),
      });
    }
    setLikes(updatedLikes);
  };

  const toggleFavorite = movieId => {
    const updatedFavs = new Set(favorites);
    if (favorites.has(movieId)) {
      updatedFavs.delete(movieId);
      fetch(`${API_BASE}/favorites.php?movie_id=${movieId}`, { method: 'GET' });
    } else {
      updatedFavs.add(movieId);
      fetch(`${API_BASE}/favorites.php?movie_id=${movieId}`, { method: 'GET' });
    }
    setFavorites(updatedFavs);
  };

  return (
    <>
      <Header />
      <div style={{ maxWidth: 1000, margin: 'auto', padding: 30, backgroundColor: '#fff', minHeight: '100vh' }}>
        <h1 style={{ color: '#000', textAlign: 'center', marginBottom: 30 }}>Welcome, User!</h1>
        {movies.length === 0 ? (
          <p style={{ textAlign: 'center', color: '#666' }}>Loading movies...</p>
        ) : (
          <div
            style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fill,minmax(300px,1fr))',
              gap: 20,
              justifyItems: 'stretch',
            }}
          >
            {movies.map(movie => (
              <MovieCard
                key={movie.id}
                movie={movie}
                liked={likes.has(movie.id)}
                favorited={favorites.has(movie.id)}
                reviews={reviews[movie.id] || []}
                onToggleLike={toggleLike}
                onToggleFavorite={toggleFavorite}
                onClick={() => router.push(`/user/movies/${movie.id}`)}
              />
            ))}
          </div>
        )}
      </div>
      <Footer />
    </>
  );
}
