# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "" 
css_dir = "../"
sass_dir = ""
images_dir = "../images"
javascripts_dir = "../../js"
fonts_dir = "../fonts/"

# You can select your preferred output style here (can be overridden via the command line):
output_style = :expanded #:compressed :expanded or :nested or  or :compact

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

# Fix the percent issue
Sass::Script::Number.precision = 10


# =================
# Disable cache hash buster
# =================

# Make a copy of sprites with a name that has no uniqueness of the hash.
on_sprite_saved do |filename|
  if File.exists?(filename)
    FileUtils.cp filename, filename.gsub(%r{-s[a-z0-9]{10}\.png$}, '.png')
  end
end

# Replace in stylesheets generated references to sprites
# by their counterparts without the hash uniqueness.
on_stylesheet_saved do |filename|
  if File.exists?(filename)
    css = File.read filename
    File.open(filename, 'w+') do |f|
      f << css.gsub(%r{-s[a-z0-9]{10}\.png}, '.png')
    end
  end
end
