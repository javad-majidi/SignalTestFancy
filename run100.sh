bar() {
  local total=$1
  local count=0

  for i in $(seq 1 $total); do
    curl -s -o /dev/null -X POST http://localhost:8005/index.php \
      -H "Content-Type: application/json" \
      -d "{\"Signal test request file\": $i}"
    ((count++))
    percent=$((count * 100 / total))
    bar=$(printf '%*s' $((percent / 2)) '' | tr ' ' '#')
    printf "\r[%s] %d%% (%d/%d)" "$bar" "$percent" "$count" "$total"
  done
  printf "\nAll done!\n"
}

bar 100