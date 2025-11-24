#!/bin/bash

echo "🚀 Starting Nuxt.js frontend setup..."

# Check if node_modules exists and has content
if [ ! -d "node_modules" ] || [ -z "$(ls -A node_modules)" ]; then
    echo "📦 Installing npm dependencies..."
    npm install
else
    echo "✅ Dependencies already installed"
fi

# Check for package.json changes and reinstall if needed
if [ package.json -nt node_modules ]; then
    echo "🔄 Package.json has been updated, reinstalling dependencies..."
    npm install
fi

echo "✅ Frontend setup completed successfully!"
echo "🌐 Starting Nuxt development server..."

# Start the development server
npm run dev