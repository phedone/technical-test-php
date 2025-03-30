#!/usr/bin/env bash

# 1. Check if Homebrew is installed; install if not
if ! command -v brew &> /dev/null
then
    echo "Homebrew not found. Installing Homebrew..."
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
    # For Apple Silicon, you may need to add Homebrew to your PATH manually
    # echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
    # eval "$(/opt/homebrew/bin/brew shellenv)"
fi

# 2. Install MySQL
echo "Installing MySQL..."
brew install mysql

# Optionally, start MySQL now or set it to launch on startup:
brew services start mysql

# 3. Install DBngin (Homebrew Cask)
echo "Installing DBngin..."
brew install --cask dbngin

# 4. Install TablePlus (Homebrew Cask)
echo "Installing TablePlus..."
brew install --cask tableplus

echo "All installations are complete!"

echo "MySQL version:"
mysql --version
echo "DBngin should now be available in /Applications or via Spotlight."
echo "TablePlus should now be available in /Applications or via Spotlight."
