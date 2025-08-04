{
  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-unstable";
  };

  outputs = { self, nixpkgs }: let
    system = "x86_64-linux";
  in {
    devShells.${system}.default = let
      pkgs = import nixpkgs { inherit system; };
    in pkgs.mkShell {
      name = "composer-devShell";
      buildInputs = [
        pkgs.php83Packages.composer
        pkgs.php83
        pkgs.nodejs_22
        pkgs.nodePackages.npm
      ];
    };
  };
}
