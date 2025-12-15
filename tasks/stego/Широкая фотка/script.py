from PIL import Image

im = Image.open("images.png")
cropped = im.crop((0, 0, 500, 500))
cropped.save("cropped.png")
