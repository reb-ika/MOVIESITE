const Reviews = () => {
  return (
    <section className="py-16 bg-gray-900">
      <div className="container mx-auto px-4">
        <h2 className="text-3xl md:text-4xl font-bold text-center text-white mb-12">
          What People Are Saying
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {Array.from({ length: 6 }).map((_, index) => (
            <div key={index} className="bg-gray-800 rounded-lg shadow-lg p-6">
              <p className="text-sm text-gray-300 mb-4">
                "Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Praesent vitae eros eget tellus tristique bibendum."
              </p>
              <div className="flex items-center">
                <div className="w-12 h-12 rounded-full bg-gray-700"></div>
                <div className="ml-4">
                  <h4 className="text-sm font-bold text-white">User {index + 1}</h4>
                  <span className="text-xs text-gray-400">Movie Enthusiast</span>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Reviews;