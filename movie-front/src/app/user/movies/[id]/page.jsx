'use client';

import React, { useEffect, useState } from 'react';
import Header from '../../../../components/Header';
import Footer from '../../../../components/Footer';

const API_BASE = 'http://localhost/MOVIESITE/api';

export default function MovieDetail({ params }) {
  const { id } = params; // movie id from URL params
  const [movie, setMovie] = useState(null);
  const [reviews, setReviews] = useState([]);
  const [userId, setUserId] = useState(1); // TODO: Replace with auth user ID
  const [rating, setRating] = useState(5);
  const [comment, setComment] = useState('');
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);

  // Fetch movie details and reviews on mount or id change
  useEffect(() => {
    async function fetchData() {
      setLoading(true);
      setError(null);
      try {
        // Fetch movie data
        const movieRes = await fetch(`${API_BASE}/movies.php?id=${id}`);
        if (!movieRes.ok) throw new Error('Failed to fetch movie data');
        const movieData = await movieRes.json();
        setMovie(movieData);

        // Fetch reviews for this movie
        const reviewsRes = await fetch(`${API_BASE}/review.php?movie_id=${id}`);
        if (!reviewsRes.ok) throw new Error('Failed to fetch reviews');
        const reviewsData = await reviewsRes.json();
        setReviews(reviewsData);
      } catch (err) {
        setError(err.message);
      }
      setLoading(false);
    }
    fetchData();
  }, [id]);

  const submitReview = async (e) => {
    e.preventDefault();
    if (!comment.trim()) {
      alert('Please enter a comment');
      return;
    }
    setSubmitting(true);
    setError(null);
    try {
      const res = await fetch(`${API_BASE}/review.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          user_id: userId,
          movie_id: Number(id),
          rating,
          comment,
        }),
      });

      const data = await res.json();
      if (!res.ok || data.error) {
        throw new Error(data.error || 'Failed to submit review');
      }

      // Append the new review locally to update UI instantly
      setReviews(prev => [
        ...prev,
        {
          id: data.id || Date.now(),
          user_id: userId,
          movie_id: Number(id),
          rating,
          comment,
          username: 'You', // or get from session/auth context
          created_at: new Date().toISOString(),
        }
      ]);

      // Reset form
      setRating(5);
      setComment('');
    } catch (err) {
      setError(err.message);
    }
    setSubmitting(false);
  };

  if (loading) return <p style={{ textAlign: 'center' }}>Loading...</p>;
  if (error) return <p style={{ textAlign: 'center', color: 'red' }}>{error}</p>;
  if (!movie) return <p style={{ textAlign: 'center' }}>Movie not found.</p>;

  return (
    <>
      <Header />
      <main style={{ maxWidth: 700, margin: '30px auto', padding: 20, backgroundColor: 'white', color: 'black', borderRadius: 12 }}>
        <h1>{movie.title} ({movie.year || ''})</h1>
        <img
          src={movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : '/placeholder.png'}
          alt={movie.title}
          style={{ width: '100%', maxHeight: 400, objectFit: 'cover', borderRadius: 12 }}
        />
        <p style={{ marginTop: 12 }}>{movie.description}</p>
        <p><b>Genre:</b> {movie.genre || 'Unknown'}</p>

        <section style={{ marginTop: 30 }}>
          <h2>Reviews</h2>
          {reviews.length === 0 ? (
            <p>No reviews yet.</p>
          ) : (
            reviews.map(r => (
              <div key={r.id} style={{ marginBottom: 15, borderBottom: '1px solid #ddd', paddingBottom: 10 }}>
                <b>{r.username || 'Anonymous'}</b> rated <b>{r.rating}/5</b>
                <p>{r.comment}</p>
              </div>
            ))
          )}
        </section>

        <section style={{ marginTop: 30 }}>
          <h2>Leave a Review</h2>
          <form onSubmit={submitReview}>
            <label>
              Rating:{' '}
              <select
                value={rating}
                onChange={e => setRating(Number(e.target.value))}
                disabled={submitting}
                required
              >
                {[5, 4, 3, 2, 1].map(num => (
                  <option key={num} value={num}>{num}</option>
                ))}
              </select>
            </label>
            <br />
            <label style={{ display: 'block', marginTop: 12 }}>
              Comment:
              <textarea
                value={comment}
                onChange={e => setComment(e.target.value)}
                disabled={submitting}
                required
                rows={4}
                style={{ width: '100%', marginTop: 6 }}
              />
            </label>
            <button type="submit" disabled={submitting} style={{ marginTop: 12, padding: '8px 16px' }}>
              {submitting ? 'Submitting...' : 'Submit Review'}
            </button>
          </form>
        </section>
      </main>
      <Footer />
    </>
  );
}
